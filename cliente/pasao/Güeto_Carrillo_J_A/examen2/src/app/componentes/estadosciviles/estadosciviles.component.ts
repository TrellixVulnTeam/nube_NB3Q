import { Component, OnInit } from '@angular/core';
import { Estadocivil } from "../../modelos/estadocivil";
import { EstadocivilService } from 'src/app/servicios/estadocivil.service';
import { Router, ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-estadosciviles',
  templateUrl: './estadosciviles.component.html',
  styleUrls: ['./estadosciviles.component.css']
})
export class EstadoscivilesComponent implements OnInit {

  public estadosciviles:Estadocivil[];
  public numEstados:number;

  constructor(private servicioEC:EstadocivilService,private ruta: Router, private route: ActivatedRoute) {
    this.estadosciviles=[];
   }

  ngOnInit() {
    this.servicioEC.getEstadosCiviles().subscribe(datos=>{
      console.log("EstadosCiviles: ", datos);
      this.estadosciviles=datos;
      this.numEstados=datos.length;
    });
  }

}
