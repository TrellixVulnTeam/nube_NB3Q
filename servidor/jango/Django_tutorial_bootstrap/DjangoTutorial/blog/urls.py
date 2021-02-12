from django.urls import path
from . import views
from django.conf import settings
from django.conf.urls.static import static

urlpatterns = [
     path('', views.home, name='home'),
     path('category/<slug:slug>', views.category, name='category'),
     path('tag/<slug:slug>', views.tag, name='tag'),
     path('post/<slug:slug>', views.post, name='post'),
] + static(settings.STATIC_URL, document_root=settings.STATIC_ROOT)