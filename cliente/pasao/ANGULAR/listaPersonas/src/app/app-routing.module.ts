import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { ListadoComponent } from './listado/listado.component';
import { PersonasAddComponent } from './personas-add/personas-add.component';


const routes: Routes = [
  {
    path: "",
    component: ListadoComponent
  },
  {
    path: "personas-add/:id",
    component: PersonasAddComponent
  }

];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
