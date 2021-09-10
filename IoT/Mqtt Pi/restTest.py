import requests
import json
import datetime


mtrR='''
[
{"id":"ELE001","pReading":"2568","thisInt":"10","provider":"BESCOM06"},
{"id":"WTR001","pReading":"256","thisInt":"3","provider":"WTRK06"},
{"id":"GAS001","pReading":"789","thisInt":"0","provider":"GASL01"}
]
'''
mtrJ=json.loads(mtrR)
for mtr in mtrJ:
	resp = requests.get('http://localhost/paymentAPI/public/api/cost/'+mtr["provider"])
	if resp.status_code != 200:
	    raise ApiError('GET /tasks/ {}'.format(resp.status_code))
	costJ=json.loads(resp.text)
	data = {}
	data['id'] = mtr["id"]
	data['pReading'] = mtr["pReading"]
	data['cons'] = mtr["thisInt"]
	data['provider'] = mtr["provider"]
	data['cost'] = float(costJ['cost'])*float(mtr["thisInt"])
	data['issue'] = str(datetime.date.today())
	json_data = json.dumps(data)
	print(json_data)
