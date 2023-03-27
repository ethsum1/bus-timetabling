# THIS CODE IS NOT USED IN THE FINAL PROJECT
# THIS IS USED AS A TEST HARNESS FOR THE POSTCODES API

import requests

postcode = "MK181AX"
endpoint = "https://api.postcodes.io/postcodes/"

response = requests.get(endpoint+postcode)

print(response.json())