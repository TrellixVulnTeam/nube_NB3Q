import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { HomeComponent } from './components/home/home.component';
import { OwnersComponent } from './components/owners/owners.component';
import { VetsComponent } from './components/vets/vets.component';
import { DetailOwnerComponent } from './components/detail-owner/detail-owner.component';
import { FormOwnerComponent } from './components/form-owner/form-owner.component';
import { PetsAndVisitComponent } from './components/pets-and-visit/pets-and-visit.component';


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
    path:"pets-and-visit",
    component: PetsAndVisitComponent
  }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
