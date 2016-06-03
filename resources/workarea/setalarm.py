import urllib2
import urllib
from crontab import CronTab

#get time to set clock
url = 'http://kvdt.eu/api/setalarm'
device_id = 'test123'

query_args = {'device_id': device_id}
data = urllib.urlencode(query_args)
request = urllib2.Request(url,data)
try:
	response = urllib2.urlopen(request).read()
except urllib2.HTTPError, error:
	response = 0
print response

#check if time is changed
file = open("/home/pi/Desktop/workarea/alarm.txt","r")
time =  file.read()
file.close()
if response != time:
        #if changed: save new time & set cron
    file.close()
    file = open("/home/pi/Desktop/workarea/alarm.txt","w")
    file.write(response)
    file.close()
    arr = response.split(":")
    hour = arr[0]
    minutes = arr[1]
    file = open("/home/pi/Desktop/workarea/id.txt","w")
    file.write(arr[3])
    file.close()

    #set cronjob
    cron = CronTab(user=True)
    cron.remove_all(comment='clock')
    job = cron.new(command='python /home/pi/Desktop/workarea/play.py', comment='clock')
    job.hour.on(hour)
    job.minutes.on(minutes)
    cron.write()
