import { Component, OnInit } from '@angular/core';
import { OwnerService } from "../../servicios/owner.service";
import { Owner } from "../../models/owner";
import { ActivatedRoute, Router } from '@angular/router';

@Component({
  selector: 'app-detail-owner',
  templateUrl: './detail-owner.component.html',
  styleUrls: ['./detail-owner.component.css']
})
export class DetailOwnerComponent implements OnInit {

  public id:any;

  public detalles:Owner;

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


}
