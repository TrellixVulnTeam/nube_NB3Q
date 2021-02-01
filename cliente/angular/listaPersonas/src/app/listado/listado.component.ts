import { Persona } from '../persona';
import { PAjaxService } from '../p-ajax.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-listado',
  templateUrl: './listado.component.html',
  styleUrls: ['./listado.component.css']
})
export class ListadoComponent implements OnInit {

  public lista:Persona[];

  constructor(private peti: PAjaxService) {
    this.peti.listar().subscribe(
      rs => {
        console.log("rs: ", rs);
        this.lista=rs;
      },
      error=>console.log("error: ", error))
    }

  ngOnInit(): void {
  }

  delete(id: number, nombre, apellidos){
    let mensaje=`¿desea borrar a ${nombre} ${apellidos} ?`;
    if(confirm(mensaje)) {
      this.peti.borrar(id).subscribe(
        datos =>{
          console.log("datos", datos);
          this.lista = datos;
          },
          error => console.log("Error: ", error));
    }
  }

}
