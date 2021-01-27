import { FormPersonaComponent } from './form-persona/form-persona.component';
import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { ListadoComponent} from '../app/listado/listado.component';


const routes: Routes = [
  {
    path:"", //miura
    component: ListadoComponent
  },
  {
    path:"persona-addmod/:id",
    component: FormPersonaComponent
  }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
