from django.shortcuts import render
from django.http import HttpResponse

def home(request):
    name = 'Antonio'

    return render(request, 'blog/home.html', {
        'my_name': name,
    })