from django.db import models
from django.conf import settings
from ckeditor.fields import RichTextField

# Create your models here.
#class Test(models.Model):
 #   text = models.CharField(max_length=100)
  #  text_area = models.TextField()
   # integer = models.IntegerField()
    #date = models.DateField()
    #boolean = models.BooleanField()
    #file = models.FileField(upload_to='files/')

    #def __str__(self):
    #	return self.text


class General(models.Model):
    name = models.CharField(max_length=100)
    logo = models.ImageField(upload_to='logo/')
    description = models.TextField()
 
    def __str__(self):
        return self.name
    

class Category(models.Model):
    name = models.CharField(max_length=200)
    slug = models.SlugField()
    description = models.TextField(blank=True)
    
    class Meta:
        verbose_name_plural = "categories"
        
        def __str__(self):
            return self.name
    

class Tag(models.Model):
    name = models.CharField(max_length=200)
    slug = models.SlugField()
    description = models.TextField(blank=True)
    
    def __str__(self):
        return self.name
    

class Post(models.Model):
    title = models.CharField(max_length=200)
    slug = models.SlugField()
    content = RichTextField()
    featured_image = models.ImageField(upload_to='images/')
    is_published = models.BooleanField(default=False)
    is_featured = models.BooleanField(default=False)
    created_at = models.DateField(auto_now=True)
    category = models.ForeignKey(Category, on_delete=models.CASCADE)
    tag = models.ManyToManyField(Tag)
    user = models.ForeignKey(settings.AUTH_USER_MODEL, on_delete=models.CASCADE)
    
    def __str__(self):
        return self.title