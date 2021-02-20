import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { HomeComponent } from './components/home/home.component';
import { OwnersComponent } from './components/owners/owners.component';
import { VetsComponent } from './components/vets/vets.component';
import { DetailOwnerComponent } from './components/detail-owner/detail-owner.component';
import { FormOwnerComponent } from './components/form-owner/form-owner.component';
import { FormPetComponent } from './components/form-pet/form-pet.component';
import { PetsComponent } from './components/pets/pets.component';
import { FormVisitComponent } from './components/form-visit/form-visit.component';
import { FormVetComponent } from './components/form-vet/form-vet.component';
import { PetTypesComponent } from './components/pet-types/pet-types.component';
import { SpecialtiesComponent } from './components/specialties/specialties.component';


const routes: Routes = [
  {
    path: "",
    component: HomeComponent
  },
  {
    path: "owners",
    component: OwnersComponent
  },
  {
    path: "vets",
    component: VetsComponent
  },
  {
    path: "owners/:id",
    component: DetailOwnerComponent
  },
  {
    path:"form-owner/:id",
    component: FormOwnerComponent
  },
  {
    path:"pets",
    component: PetsComponent
  },
  {
    path: "form-pet/:id",
    component:FormPetComponent
  },
  {
    path: "form-pet-mod/:idPet",
    component:FormPetComponent
  },
  {
    path: "form-visit-add/:idPet",
    component:FormVisitComponent
  },
  {
    path: "visit-mod/:idVisit/:idPet",
    component:FormVisitComponent
  },
  {
    path: "form-vet-add",
    component:FormVetComponent
  },
  {
    path: "form-vet-mod/:idVet",
    component:FormVetComponent
  },
  {
    path: "pet-types",
    component:PetTypesComponent
  },
  {
    path: "specialties",
    component:SpecialtiesComponent
  }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
