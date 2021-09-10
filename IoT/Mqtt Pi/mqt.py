import paho.mqtt.client as paho
import serial
import RPi.GPIO as GPIO
import requests
import json
import datetime
import time

# Server props
broker = "192.168.43.183"
port = 1883

# Slave props
ser = serial.Serial("/dev/ttyACM0", 9600)
ser.baudrate = 9600

def on_publish(client, userdata, result):
    print("data published \n")
pass


# creating client
client1 = paho.Client("control1")# create client object
client1.on_publish = on_publish# assign

#function to callback
client1.connect(broker, port)

st = ''
time.sleep(2)
ser.write('p')
flag=0
while (1):
    now = datetime.datetime.now()
    if(now.day==10):
        while(flag==0): 
                read_ser = ser.readline()
                st += read_ser# ser.write('e')
                if (read_ser[0] == ']'):
                        mtrJ = json.loads(st)
                        for mtr in mtrJ:
                                resp = requests.get('http://192.168.43.183/paymentAPI/public/api/cost/' + mtr["provider"])
                                if resp.status_code != 200:
                                        raise ApiError('GET /tasks/ {}'.format(resp.status_code))
                                costJ = json.loads(resp.text)
                                data = {}
                                data['id'] = mtr["id"]
                                data['pReading'] = mtr["pReading"]
                                data['cons'] = mtr["thisInt"]
                                data['provider'] = mtr["provider"]
                                data['cost'] = float(costJ['cost']) * float(mtr["thisInt"])
                                json_data = "REC: "+json.dumps(data)
                                strS=str(json_data)
                                ret = client1.publish("readings", strS)
                                print(json_data)

