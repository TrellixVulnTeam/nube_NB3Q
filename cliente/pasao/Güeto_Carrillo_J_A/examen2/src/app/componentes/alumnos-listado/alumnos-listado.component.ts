import { Component, OnInit } from '@angular/core';
import { Alumno } from 'src/app/modelos/alumno';
import { Router, ActivatedRoute } from '@angular/router';
import { AlumnoService } from 'src/app/servicios/alumno.service';

@Component({
  selector: 'app-alumnos-listado',
  templateUrl: './alumnos-listado.component.html',
  styleUrls: ['./alumnos-listado.component.css']
})
export class AlumnosListadoComponent implements OnInit {

  public alumnos:Alumno[];
  public numAlumnos:number;

  constructor(private servicioAlum:AlumnoService,private ruta: Router, private route: ActivatedRoute) {
    this.alumnos=[];
   }

  ngOnInit() {
    this.servicioAlum.getAlumnos().subscribe(datos=>{
      this.alumnos=datos;
      this.numAlumnos=datos.length;
    });
  }

}
