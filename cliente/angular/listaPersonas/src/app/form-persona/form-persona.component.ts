import { PAjaxService } from './../p-ajax.service';
import { Persona } from './../persona';
import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';


@Component({
  selector: 'app-form-persona',
  templateUrl: './form-persona.component.html',
  styleUrls: ['./form-persona.component.css']
})
export class FormPersonaComponent implements OnInit {

  public persona:Persona;

  constructor(private peti:PAjaxService, private ruta:Router, private route:ActivatedRoute) {
    this.persona=<Persona>{};
  /*
    this.persona={
      ID: -1,
      DNI: "20538956W",
      NOMBRE: "Manuel",
      APELLIDOS: "Martín Fernández"
    }
    */

  }

  ngOnInit(): void {
    this.persona.id= this.route.snapshot.params["id"];

    if(this.persona.id!=-1){
      this.peti.selPersonaId(this.persona.id).subscribe(
        datos =>{
          console.log("datos", datos);
          this.persona=datos;
        },
        error => console.log("Error: ", error));
    }
  }

  addmod() {

    if(this.persona.id==-1){

      console.log("persona :", this.persona);
 /*
    let nuevo= JSON.parse(JSON.stringify(this.persona));

    let p = {
      servicio: "insertar",
      dni: this.persona.DNI,
      nombre: this.persona.NOMBRE,
      apellidos: this.persona.APELLIDOS
    };

    */
   this.peti.anade(this.persona).subscribe(
    datos =>{
    console.log("datos", datos);
    this.ruta.navigate(['/']);
  },
  error => console.log("Error: ", error));


  } else {
    this.peti.modificar(this.persona).subscribe(
      datos =>{
      console.log("datos", datos);
      this.ruta.navigate(['/']);
      },
      error => console.log("Error: ", error));
    }
  }


}
