from django.contrib import admin
from .models import Centro, Departamento
# Register your models here.

class CentroAdmin(admin.ModelAdmin):
    prepopulated_fields = {"slug": ("nombre",)}
  

class DepartamentoAdmin(admin.ModelAdmin):
    prepopulated_fields = {"slug": ("nombre",)}
    list_display = ('nombre', 'presupuesto')
 
admin.site.register(Centro, CentroAdmin)
admin.site.register(Departamento, DepartamentoAdmin)
