from django.db import models
from django.conf import settings
from ckeditor.fields import RichTextField

# Create your models here.
class Test(models.Model):
    text = models.CharField(max_length=100)
    text_area = models.TextField()
    integer = models.IntegerField()
    date = models.DateField()
    boolean = models.BooleanField()
    file = models.FileField(upload_to='files/')

    def __str__(self):
    	return self.text

class General(models.Model):
    name = models.CharField(max_length=100)
    logo = models.ImageField(upload_to='logo/')
    description = models.TextField()
 
    def __str__(self):
        return self.name
