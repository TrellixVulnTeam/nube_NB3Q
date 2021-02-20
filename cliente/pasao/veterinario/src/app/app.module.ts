import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { HttpClientModule } from '@angular/common/http';
import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { HomeComponent } from './components/home/home.component';
import { OwnersComponent } from './components/owners/owners.component';
import { VetsComponent } from './components/vets/vets.component';
import { DetailOwnerComponent } from './components/detail-owner/detail-owner.component';
import { FormOwnerComponent } from './components/form-owner/form-owner.component';
import { FormsModule } from '@angular/forms';
import {NgbModule} from '@ng-bootstrap/ng-bootstrap';
import { FormPetComponent } from './components/form-pet/form-pet.component';
import { PetsComponent } from './components/pets/pets.component';
import { VisitListComponent } from './components/visit-list/visit-list.component';

import { FormVisitComponent } from './components/form-visit/form-visit.component';
import { FormVetComponent } from './components/form-vet/form-vet.component';
import { PetTypesComponent } from './components/pet-types/pet-types.component';
import { SpecialtiesComponent } from './components/specialties/specialties.component';


@NgModule({
  declarations: [
    AppComponent,
    HomeComponent,
    OwnersComponent,
    VetsComponent,
    DetailOwnerComponent,
    FormOwnerComponent,
    FormPetComponent,
    PetsComponent,
    VisitListComponent,
    FormVisitComponent,
    FormVetComponent,
    PetTypesComponent,
    SpecialtiesComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule,
    FormsModule,
    NgbModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
