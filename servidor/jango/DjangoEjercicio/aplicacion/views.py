from django.shortcuts import render
from .models import Centro, Departamento

# Create your views here.
def home(request):
	centros = Centro.objects.all()
	return render(request, 'aplicacion/home.html', {
        'centros': centros,
    })

def centro(request):
	centros = Centro.objects.all()
	return render(request, 'aplicacion/home.html', {
        'centros': centros,
    })

def departamento(request, slugCentro):
	departamentos = Departamento.objects.filter(centro__slug=slugCentro)
	centro = Centro.objects.get(slug=slugCentro)
	return render(request, 'aplicacion/departamentos.html', {
        'centro': centro,
        'departamentos': departamentos,
    })