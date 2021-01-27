import { Component, OnInit } from '@angular/core';
import { PAjaxService } from "../p-ajax.service";
import { Provincia } from '../provincia';
import { Localidades } from '../localidades';

@Component({
  selector: 'app-provloc',
  templateUrl: './provloc.component.html',
  styleUrls: ['./provloc.component.css']
})
export class ProvlocComponent implements OnInit {

  //public opcionesProv: Array<Provincia>;
  public opcionesProv: Provincia[];
  //public opcionesLoc: Localidades[];
  public opcionesLoc: Array<Localidades>;
  public opSelProv: any;
  public opSelLoc: any;

  constructor(private peti: PAjaxService) {
    this.opcionesProv = [];
    this.opSelProv = null;
  }

  ngOnInit(): void {

    
    //quitar barrabaja al subscribe
    this.peti.pedirProvincias().subscribe(
      datos => {
        datos[1].CODIGO;
        console.log("datos: ", datos);
        console.log("datos: ",  datos[1].CODIGO);
        this.opcionesProv = datos;
        //this.opcionesProv.unshift({ CODIGO: -1, NOMBRE: "seleccionar provincia" });
        //this.opSelProv = datos[1];
      },
      error => console.log("Error: ", error));
  } 


  seleccionProv() {
    console.log("this.opSelProv", this.opSelProv);
    // console.log("opcion: ", opcion);

    this.peti.pedirLocalidades(this.opSelProv).subscribe(
      datos => {
        console.log("datos: ", datos);
        this.opcionesLoc = datos;
        this.opSelLoc = datos[1];
      },
      error => console.log("Error: ", error));
  }

  seleccionLoc() {
  console.log("localidad",this.opSelLoc)
  }

}
