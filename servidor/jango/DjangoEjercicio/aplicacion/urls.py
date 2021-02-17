from django.urls import path
from . import views
from django.conf import settings
from django.conf.urls.static import static

urlpatterns = [
    #path('', views.home),
    path('', views.home, name='home'),
    path('centros', views.centro, name='centro'),
    path('centro/<slug:slugCentro>', views.departamento, name='departamentos'),
]+ static(settings.MEDIA_URL, document_root=settings.MEDIA_ROOT,) + static(settings.STATIC_URL, document_root=settings.STATIC_ROOT)
