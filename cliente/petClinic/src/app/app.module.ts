import { HttpClientModule } from '@angular/common/http';
import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { HomeComponent } from './components/home/home.component';
import { OwnersComponent } from './components/owners/owners.component';
import { DetailOwnerComponent } from './components/detail-owner/detail-owner.component';
import { FormOwnersComponent } from './components/form-owners/form-owners.component';
import { FormsModule } from '@angular/forms';
import { PettypeListComponent } from './components/pettype-list/pettype-list.component';
import { PettypeAddComponent } from './components/pettype-add/pettype-add.component';

@NgModule({
  declarations: [
    AppComponent,
    HomeComponent,
    OwnersComponent,
    DetailOwnerComponent,
    FormOwnersComponent,
    PettypeListComponent,
    PettypeAddComponent,
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule,
    FormsModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
