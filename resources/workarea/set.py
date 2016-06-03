#setting cronjob for checking every minute if the clock has changed
from crontab import CronTab

cron = CronTab(user=True)
cron.remove_all()
job = cron.new(command='python /home/pi/Desktop/workarea/setalarm.py')
job.minute.every(1)
cron.write()
print cron.render()


file = open("/home/pi/Desktop/workarea/alarm.txt","w")
file.write('0')
file.close()
