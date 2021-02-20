import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Alumno } from 'src/app/modelos/alumno';
import { Estadocivil } from '../modelos/estadocivil';

@Injectable({
  providedIn: 'root'
})
export class AlumnoService {

  private url = "http://localhost/ANGULAR/Servicios/servicios.php";

  constructor(private http: HttpClient) { }

  getAlumnos() {
    let peticion = JSON.stringify({
      accion: 3
    });
    return this.http.post<Alumno[]>(this.url, peticion);
  }

  anadeAlumno(objetoAlumno:Alumno) {
    console.log("llega", objetoAlumno);
    let peticion = JSON.stringify({
      accion: 0,
      NOMBRE: objetoAlumno.NOMBRE,
      APELLIDOS: objetoAlumno.APELLIDOS,
      SEXO: objetoAlumno.SEXO,
      FECHA_NACIMIENTO: objetoAlumno.FECHA_NACIMIENTO,
      ESTADO_CIVIL: objetoAlumno.ESTADO_CIVIL
    });
    console.log("kw paso ", peticion)
    return this.http.post(this.url, peticion);
  }

  modificaAlumno(objetoAlumno:Alumno){
    let peticion = JSON.stringify({
      accion: 1,
      NOMBRE: objetoAlumno.NOMBRE,
      APELLIDOS: objetoAlumno.APELLIDOS,
      SEXO: objetoAlumno.SEXO,
      FECHA_NACIMIENTO: objetoAlumno.FECHA_NACIMIENTO,
      ESTADO_CIVIL: objetoAlumno.ESTADO_CIVIL,
      ID: objetoAlumno.ID
    });

    return this.http.post(this.url, peticion);
  }

  cargaAlumId(ide:any){
    let peticion = JSON.stringify({
      accion: 4,
      ID:ide
    });
    return this.http.post<Alumno>(this.url, peticion);
  }

}
