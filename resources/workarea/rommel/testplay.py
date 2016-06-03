import pygame.mixer
from pygame.mixer import Sound

from gpiozero import Button
from signal import pause


buttonStop = Button(2)
buttonSnooze =  Button(3)

pygame.mixer.init()
s = pygame.mixer.Sound("/home/pi/Desktop/workarea/police_s.wav")
playing = True
#while playing:
s.play()

#buttonStop.when_pressed = snooze

#while playing:
 #   pause()

playing = True
while playing:
        if buttonStop.is_pressed:
            s.stop()
            playing = False
            
        elif buttonSnooze.is_pressed
            s.stop()
            playing = False
            
        
