import pygame.mixer
from pygame.mixer import Sound

from gpiozero import Button
from crontab import CronTab

import urllib2
import urllib

import datetime

#if snooze is pressed to much
def emergency():
        url = 'http://kvdt.eu/api/emergency'
        device_id = 'test123'
        file = open("/home/pi/Desktop/workarea/id.txt","r")
        alarm_id = file.read()
        file.close()
        print alarm_id
        
        query_args = {'device_id': device_id,'alarm_id':int(alarm_id)}
        data = urllib.urlencode(query_args)
        request = urllib2.Request(url,data)
        try:
                response = urllib2.urlopen(request).read()
        except urllib2.HTTPError, error:
                response = 'fail'
        print response
        return;

#minuut aan snoozen -> needed for check
settime = datetime.datetime.now()

buttonStop = Button(2)
buttonSnooze =  Button(3)

cron = CronTab(user=True)
cron.remove_all()

pygame.mixer.init()
s = pygame.mixer.Sound("/home/pi/Desktop/workarea/police_s.wav")
s.play()

playing = True
while playing:
        # snooze
        if buttonSnooze.is_pressed or settime < datetime.datetime.now() - datetime.timedelta(seconds = 60):
            s.stop()
            playing = False
            cron.remove_all(comment='setalarm')
            cron.remove_all(comment='clock')
            job = cron.new(command='python /home/pi/Desktop/workarea/play.py', comment='clock')
            job.minute.every(1)
            cron.write()
            print cron.render()
            snoozefile = open("/home/pi/Desktop/workarea/snooze.txt","r")
            snooze =  snoozefile.read()
            snoozefile.close()
                #checken hoeveel er gesnoozed is 
            if int(snooze) != 1:
                  snooze = int(snooze) + 1                  
                  snoozefile = open("/home/pi/Desktop/workarea/snooze.txt","w")
                  snoozefile.write(str(snooze))
                  snoozefile.close()
            else: #teveel gesnoozed >3
                  print ('in')
                  s.stop()
                  playing = False
                  emergency()
                  cron.remove_all(comment='setalarm')
                  cron.remove_all(comment='clock')
                  job = cron.new(command='python /home/pi/Desktop/workarea/setalarm.py', comment='setalarm')
                  job.minute.every(1)
                  cron.write()
                  snoozefile = open("/home/pi/Desktop/workarea/snooze.txt","w")
                  snoozefile.write('0')
                  snoozefile.close()
        #alarm stoppen
        elif buttonStop.is_pressed:
            s.stop()
            playing = False
            job = cron.new(command='python /home/pi/Desktop/workarea/setalarm.py', comment='setalarm')
            job.minute.every(1)
            cron.write()
            print cron.render()
            snoozefile = open("/home/pi/Desktop/workarea/snooze.txt","w")
            snoozefile.write('0')
            snoozefile.close()
        
        
