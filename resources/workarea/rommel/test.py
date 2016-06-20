import datetime
settime = datetime.datetime.now()
loop = True
while loop:
        if settime < datetime.datetime.now() - datetime.timedelta(seconds = 60):
               loop = False
print 'snooze'
