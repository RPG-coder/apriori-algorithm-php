<?php
    session_start();
    $User_DB = $_SESSION['User_DB'];
    $method  = $_POST['itemset_method'];
    $support = $_POST['min_support']/100;
    $confidence = $_POST['min_confidence']/100;

    /* -- SQL Config -- */
    $servername = "localhost";
    $username = "root";
    $password = "root123";
    $dbname = "cs634dmadmin";

    echo "Min-Support: $support<br/>Min-Confidence: $confidence<br/><br/>";
    set_time_limit(30000); /* Max PHP Execution time: 15-minutes */
    function getItemset(){
        global $servername, $username, $password, $dbname;
        $conn = new mysqli($servername, $username, $password, $dbname);
        $item_array = array();
        if ($conn->connect_error) {
            return NULL;
        }else{
            $sql = "SELECT label FROM shopping_list";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row=$result->fetch_array(MYSQLI_ASSOC)){
                    array_push($item_array, $row['label']);
                }
            } else {
                return NULL;
            }
        }
        return $item_array;
    }

    function support($X){
        global $servername, $username, $password, $User_DB;
        $conn = new mysqli($servername, $username, $password, $User_DB);
        
        $X_array = explode(',', $X);
        $sql = "SELECT (count(A.Tid) / (SELECT count(*) FROM Transactions)) AS support FROM TransactionDetails A WHERE A.label = '".$X_array[0]."'";
        $x=0;
        for($x=1;$x<count($X_array);$x++){
            $sql .= " and A.Tid IN (SELECT B.Tid FROM TransactionDetails B WHERE B.label = '".$X_array[$x]."')";
        }
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $support_count = $result->fetch_array(MYSQLI_ASSOC)['support'];
            return $support_count;
        }return 0;
    }

    function confidence($X, $Y){ return support(implode(",", array_merge($X, $Y)))/support(implode(",", $X)); }

    function pruningCandidateSet($c){ 
        global $support;
        return support($c) >= $support; 
    }

    function GenerateList($L){
        $result = array();
        $item_count = count($L);
        for($a=0 ; $a < $item_count ; $a++){
            for($b=$a+1 ; $b < $item_count ; $b++){
                list($p, $q) = [$L[$a], $L[$b]];
                $p_array = explode(',', $p);
                $q_array = explode(',', $q);
                $i = 0;
                
                for($i=0;$i < count($p_array) - 1;$i++){
                    if($p_array[$i] !== $q_array[$i]) break;
                }if( ($i === count($p_array)-1) && ($p_array[$i] !== $q_array[$i]) ){
                    $p_array_copy = array_merge(array(), $p_array);
                    array_push($p_array_copy, $q_array[$i]);
                    $c = implode(',', $p_array_copy);
                    array_push($result, $c);
                }
            }
        }
        return $result;
    }

    function powerSet($set){
        $powerset = array(array());
        foreach($set as $element){
            foreach($powerset as $combination){
                array_push($powerset, array_merge(array($element), $combination));
            }
        }return $powerset;
    }
    function printAssociationRules($frequent_itemset){
        global $confidence;
        for($i=0;$i<count($frequent_itemset);$i++){
            echo "FREQ-Itemset ".($i+1).": {".$frequent_itemset[$i]."} <br/>";
            $itemset = explode(",", $frequent_itemset[$i]);
            $subsets = powerSet($itemset);

            array_shift($subsets);array_pop($subsets);

            echo "<ul>";
            foreach($subsets as $subset){
                $subset_confidence = confidence($subset,array_diff($itemset, $subset));
                if($subset_confidence >= $confidence){
                    $subset_support = support(implode(",", array_diff($itemset, $subset)));
                    echo "<li>".implode(",", $subset)."&rarr; ".implode(",", array_diff($itemset, $subset))." (Support: $subset_support, Confidence:$subset_confidence)</li>";
                }
            }echo "</ul>";
            
        }
    }

    if($method=='apriori'){
        function Apriori(){
            list($L,$C) = [array(), array()];
            $C[0] = getItemset();
            $L[0] = array_values(array_filter($C[0], 'pruningCandidateSet')); /* Frequent Itemset-1 */
            if(!$L[0]) return $L[0];
            $k=0;
            do{ /* Apriori: Choose (k+1)th Candidates from subset of kth Frequent Itemset */
                $C[$k+1] = GenerateList($L[$k]);
                $L[$k+1] = array_values(array_filter($C[$k+1], 'pruningCandidateSet'));
                $k++;
            }while($L[$k]);
            return $L[$k-1];
        }

        /* Generating Association Rules */
        echo "Type: Apriori (Frequent-itemset generation)<br/>";
        echo "Output: <br/>";
        $startTime=microtime(TRUE);
        $frequent_itemset = Apriori();
        printAssociationRules($frequent_itemset);

        echo "Running Time:<br/>";
        $endTime=microtime(TRUE);
        $timeDiff=$endTime-$startTime;
        echo "Start time: ".number_format($startTime, 5, '.', '')." seconds<br/>End time: ".number_format($endTime, 5, '.', '')." seconds<br/>Time Difference: ".number_format($timeDiff, 5, '.', '')." seconds elapsed.<br/>";

    }else if($method=='bruteforce'){
        function BruteForce(){
            list($L,$C) = [array(), array()];
            $C[0] = getItemset();
            $L[0] = array_values(array_filter($C[0], 'pruningCandidateSet')); /* Frequent Itemset-1 */
            if(!$L[0]) return $L[0];
            $k=0;
            do{ /* BruteForce: Choose (k+1)th Candidates from entire Shopping list space */
                $C[$k+1] = GenerateList($C[$k]); /* Choose from Entire Shopping list */
                $L[$k+1] = array_values(array_filter($C[$k+1], 'pruningCandidateSet'));
                $k++;
            }while($L[$k]);
            return $L[$k-1];
        }

        /* Generating Association Rules */
        echo "Type: BruteForce (Frequent-itemset generation)<br/>";
        echo "Output: <br/>";
        $startTime=microtime(TRUE);
        $frequent_itemset = BruteForce();
        printAssociationRules($frequent_itemset);

        echo "Running Time:<br/>";
        $endTime=microtime(TRUE);
        $timeDiff=$endTime-$startTime;
        echo "Start time: ".number_format($startTime, 5, '.', '')." seconds<br/>End time: ".number_format($endTime, 5, '.', '')." seconds<br/>Time Difference: ".number_format($timeDiff, 5, '.', '')." seconds elapsed.<br/>";
    }
?>