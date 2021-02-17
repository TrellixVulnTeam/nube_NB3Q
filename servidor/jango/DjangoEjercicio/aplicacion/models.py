from django.db import models

# Create your models here.
class Centro(models.Model):
    nombre = models.CharField(max_length=100)
    slug = models.SlugField()
    direccion = models.CharField(max_length=255,default="")

    def __str__(self):
        return self.nombre

class Departamento(models.Model):
    nombre = models.CharField(max_length=200)
    slug = models.SlugField()
    presupuesto = models.IntegerField()
    centro = models.ForeignKey(Centro, on_delete=models.CASCADE)

    def __str__(self):
        return self.nombre
