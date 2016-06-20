import pygame.mixer
from pygame.mixer import Sound

from gpiozero import Button
from crontab import CronTab

import urllib2
import urllib

#if snooze is pressed to much
def emergency():
        print 'in'
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

emergency()
