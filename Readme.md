# Apriori Algorithm (Midterm-project)
#### By Rahul Gautham Putcha - Under the guidence of Prof. Jason Wang

## Quick Links
- [Setting up the Environment & Running the project](#setting-up-the-environment--running-the-project)
- [Project: Short Decription (Apriori algorithm)](#project-short-decription-apriori-algorithm)
- [Results](#results)
- [More details are presented in the User manual shared with this assignment](#more-details)

## Setting up the Environment & Running the project
- Install [Xaamp](https://www.apachefriends.org/download.html) for Windows, Linux or Mac
- MySQL/MariaDB is required for setting up a database. By default, Xaamp installs MySQL (MariaDB)
- Xaamp also installs Apache HTTP server
- Setup the root for MySQL and start Xaamp
- Inside of Xaamp, run Apache and MySQL by clicking on Start buttons
- Open Browser and view the root page of the project from localhost, http://localhost/<project folder path\>


## Project: Short Decription (Apriori algorithm)
- **Apriori Algorithm:** describes that the superset of infrequent sets are always infrequent. Or we can say that a group of items are frequent only if the individual items within the group are frequently picked. This approach is the best way to reduce the search size of the set space and thereby, making the process of finding the frequent itemsets to be more efficient.
- To achieve this, we go by playing around with two metrics support and confidence score. 
- Using these metrics, we shortlist larger sets containing infrequent items over a **supporting threshold** and reach to frequent item sets
- These item sets are then checked on **confidence of them being frequent** and Gather all associations describing which items are intimatly related. Question this part solve is:
  - In what pattern are these items bought together? (If A and B are brought together, is it A which when bought leads to B being bought or the other way around.)
  - How often they are bought together? (Confidence of them being frequent. Higher the value more the chances of them being together.)
  
For more details on the apriori algorithm, Please visit [Agrawal .et al](https://rakesh.agrawal-family.com/papers/vldb94apriori.pdf)

## Results
- By the end of this assignment we would also get a set of association rules that we can confidently give a say that the associated items are bought together more often. 
- Hence, Apriori is one of the best approach for building shopping recommendations system, due to their time-boxed nature of response involved.
- This assignment also gives a tradeoff between the regular Frequent set generation method and Apriori algorithm with the estimating metric as time to complete.

## More details
- Along with is assignment the User manual which goes into more details on how the assignment is structured.
- User manual: Refer to `putcha_rahul gautham_midtermproj.pdf`

  
## More Updates comming soon ...
  
<h3 align=center>Happy Using</h3>
