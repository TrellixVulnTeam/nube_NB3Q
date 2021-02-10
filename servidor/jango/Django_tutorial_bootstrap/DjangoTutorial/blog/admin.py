from django.contrib import admin

from .models import General, Category, Tag, Post

# Register your models here.
#class TestAdmin(admin.ModelAdmin):
 #   date_hierarchy = 'date'
  #  list_display = ('text', 'integer', 'date', 'boolean')
   # list_editable = ('integer', 'boolean')
    #ordering = ('text', 'date')
    #search_fields = ('text', 'integer')

 
#admin.site.register(Test, TestAdmin)

admin.site.register(General)

class CategoryAdmin(admin.ModelAdmin):
    prepopulated_fields = {"slug": ("name",)}
    
    
class TagAdmin(admin.ModelAdmin):
    prepopulated_fields = {"slug": ("name",)}
    
    
class PostAdmin(admin.ModelAdmin):
    prepopulated_fields = {"slug": ("title",)}
    admin.site.register(General)
    admin.site.register(Category, CategoryAdmin)
    admin.site.register(Tag, TagAdmin)
    admin.site.register(Post, PostAdmin)