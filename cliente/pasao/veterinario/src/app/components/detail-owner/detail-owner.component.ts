import { Component, OnInit } from '@angular/core';
import { OwnerService } from "../../servicios/owner.service";
import { Owner } from "../../models/owner";
import { ActivatedRoute, Router } from '@angular/router';
import { Pet } from 'src/app/models/pet';

@Component({
  selector: 'app-detail-owner',
  templateUrl: './detail-owner.component.html',
  styleUrls: ['./detail-owner.component.css']
})
export class DetailOwnerComponent implements OnInit {

  public id:any;

  public detalles:Owner;

  public animales:Pet[];

  constructor(private servicioOwner:OwnerService,private ruta: Router, private route: ActivatedRoute) {
    this.detalles= <Owner>{};
   }

  ngOnInit() {
    this.id = this.route.snapshot.params["id"];
    this.servicioOwner.getOwnerId(this.id).subscribe(
      datos => {
        console.log(datos);
        this.detalles = datos;
      }
    );
    
    this.servicioOwner.getOwnerIdPets(this.id).subscribe(datos=>{
      this.animales=datos.pets;
      console.log(datos);
    });
  }

  eliminar(ide:any){
    if(confirm("¿Desea eliminar al Owner?")){
      this.servicioOwner.eliminaOwner(ide).subscribe(datos =>{
       this.ruta.navigate(['/owners']);
      });
    }
  }

  irAmod(ide:any){

    this.ruta.navigate(['/form-owner/'+ide]);
  }

  irAnadePet(ide:any){
    this.ruta.navigate(['/form-pet/'+ide]);
  }


  actualizaPetsEliminados(evento:any){
    if(evento.result=="OK"){
			this.servicioOwner.getOwnerIdPets(this.id).subscribe(datos=>{
        this.animales=datos.pets;
      });
	  }
  }

}
