import asyncio
import aiohttp
import requests
from flask import json


async def send_data_to_master():
    parameters = {
        'Master': Master,
        'Algo': Algo,
        'Approach': Approach,
        'min_support': min_support,
        'min_confidence': min_confidence,
        'c_size': c_size,
        'nrows': nrows
    }

    async with aiohttp.ClientSession() as session:
        await asyncio.wait([session.post('http://127.0.0.1:5000/clientdata', json=parameters)])

if __name__ == "__main__":
    Master = input("MasterID:")
    Algo = input("Algorithm:(FP/AP/EC):")
    Approach = input("Approach:(H/V/HY):")
    nrows = int(input("Enter Nrows:"))
    min_support = float(input("Enter the Minimum Support:"))
    min_confidence = float(input("Enter the Minimum Confidence:"))
    c_size = int(input("Custer Size:"))
    loop = asyncio.get_event_loop()
    loop.run_until_complete(send_data_to_master())
    loop.close()
    resp = requests.post("http://127.0.0.1:5000/assigntask")
    text = json.loads(resp.text)
    print(text["message"], "Code:", resp.status_code)
    if resp.status_code == 200:
        resp1 = requests.post("http://127.0.0.1:5000/implement")
        text = json.loads(resp1.text)
        print(text['message'], "Code:", resp1.status_code)
    else:
        print("Some Error Occurred In Assignment \nError Code:", resp.status_code)
    if Approach == 'H':                 #MAKE CHANGES
        resp3 = requests.post("http://127.0.0.1:5000/generate_rules_Consolidated")
        text = json.loads(resp3.text)
        print(text['message'], "Code:", resp3.status_code)
    elif Approach == 'V':                #MAKE CHANGES
        resp2 = requests.post("http://127.0.0.1:5000/generate_rules")
        text = json.loads(resp2.text)
        print(text['message'], "Code:", resp2.status_code)
    elif Approach == 'HY':               #MAKE CHANGES
        resp3 = requests.post("http://127.0.0.1:5000/generate_rules_Consolidated_HY")
        text = json.loads(resp3.text)
        print(text['message'], "Code:", resp3.status_code)
