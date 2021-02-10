from django.shortcuts import render

# Create your views here.
from django.http import HttpResponse
from .models import General

def home(request):
    general = General.objects.first()
 
    return render(request, 'blog/home.html', {
        'general': general,
    })
