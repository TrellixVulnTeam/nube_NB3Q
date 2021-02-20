import { Component, OnInit } from '@angular/core';
import { OwnerService } from "../../servicios/owner.service";
import { Owner } from "../../models/owner";
import { ActivatedRoute, Router } from '@angular/router';

@Component({
  selector: 'app-form-owner',
  templateUrl: './form-owner.component.html',
  styleUrls: ['./form-owner.component.css']
})
export class FormOwnerComponent implements OnInit {

  public owner:Owner;
  public iden:any;
  public boton:string = "Añadir";

  constructor(private servicioOwner:OwnerService,private ruta: Router, private route: ActivatedRoute) {
    this.owner= <Owner>{};

  }

  anadeMod(){
    if(this.iden!=-1){
      this.modificar();
    }
    else{
      this.anadir();
    }
  }

  anadir(){
    this.boton="Añadir";
    console.log(this.owner);
    this.servicioOwner.anadeOwner(this.owner).subscribe(datos =>{
      this.owner = datos;
      console.log(this.owner);
      this.ruta.navigate(['/owners']);
    });
  }

  modificar(){

    this.servicioOwner.modOwner(this.owner).subscribe(
      datos => {
        console.log(datos);
      });
      this.ruta.navigate(['/owners']);
  }

  ngOnInit() {
    this.iden = this.route.snapshot.params["id"];
    if(this.iden!=-1){
      this.boton="Modificar";
      this.servicioOwner.getOwnerId(this.iden).subscribe(
        datos => {
          console.log(datos);
          this.owner = datos;
        }
      );
    }
    else{
      this.boton="Añadir";
    }
  }

}
