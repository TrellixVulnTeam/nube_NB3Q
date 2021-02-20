import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { InicioComponent } from './componentes/inicio/inicio.component';
import { AlumnosListadoComponent } from './componentes/alumnos-listado/alumnos-listado.component';
import { EstadoscivilesComponent } from './componentes/estadosciviles/estadosciviles.component';
import { AlumnoFormComponent } from './componentes/alumno-form/alumno-form.component';


const routes: Routes = [
  {
    path: "",
    component: InicioComponent
  },
  {
    path: "alumnos-list",
    component: AlumnosListadoComponent
  },
  {
    path: "estadosciviles",
    component: EstadoscivilesComponent
  },
  {
    path: "alumno-form",
    component: AlumnoFormComponent
  },
  {
    path: "alumno-form-mod/:id",
    component: AlumnoFormComponent
  }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
