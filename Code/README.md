# Code Overview
## 1. [Client.py](https://github.com/AbhishekSoni98/BE_Project/blob/master/Code/Client.py)
- Intial CLI to interact with the Master API (Might not work with current version of the API, please configure it before using or use the GUI)
## 2. [CSV_to_DB.py](https://github.com/AbhishekSoni98/BE_Project/blob/master/Code/CSV_to_DB.py)
- Slow and Naive Script to load the Transactions in DB Tables.
## 3. [DB_Service.py](https://github.com/AbhishekSoni98/BE_Project/blob/master/Code/DB_Service.py)/ [Storage.py](https://github.com/AbhishekSoni98/BE_Project/blob/master/Code/Storage.py)
- Faster and Better Script to load the  Transactions in DB Tables.
## 4. [Master.py](https://github.com/AbhishekSoni98/BE_Project/blob/master/Code/Master.py)
- Driver API that handles all the Slaves and finalzes the Final FIS's and Rules.
##### Key Functions
- 4.1 Validating Inputs for Master and Creating Sub-Tasks in all Approaches implemented in our project.
- 4.2 Handling, Managing and Communicating with the Slave API's.
- 4.3 Handling and Managing all the Sub-Tasks using Task Queue untill their completion.
- 4.4 Summarizing FIS's (for Horizontal/Vertical/Hybrid Approaches) and Generating Rules and updating the same in central DB Tables.
- 4.5 Managing a multi-threaded environment for a given Master task to achieve asynchorous task distribution and acheiving an overall parallel distributed Architecture.

## 5.[Slave1.py](https://github.com/AbhishekSoni98/BE_Project/blob/master/Code/Slave.py),2,3,4
- Worker API that depending on the Inputs computes the FIS's.
##### Key Functions
- 5.1 Basic Data Processing and Data Formatting for each specific Algorithm and Approach.
- 5.2 Contains all functions for different Algorithms using different approaches.
- 5.3 Contains a New proposed Algorithm that is more efficient and faster on Larger Transaction set.
- 5.4 Intermediate FIS generation and updating it in central DB Tables. 
