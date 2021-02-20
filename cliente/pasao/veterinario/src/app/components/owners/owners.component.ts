import { Component, OnInit } from '@angular/core';
import { OwnerService } from "../../servicios/owner.service";
import { Owner } from "../../models/owner";
import { ActivatedRoute, Router } from '@angular/router';

@Component({
  selector: 'app-owners',
  templateUrl: './owners.component.html',
  styleUrls: ['./owners.component.css']
})
export class OwnersComponent implements OnInit {

  public owners:Array<Owner>;
  public muestra:boolean = false;

  constructor(private servicioOwner:OwnerService,private ruta: Router, private route: ActivatedRoute) { }

  ngOnInit() {

    this.servicioOwner.getOwners().subscribe(
      datos => {
        console.log(datos);
        this.owners = datos;
      }
    );
  }

  mostrarForm(){
    this.muestra=true;
  }

  eliminar(ide:any){
    if(confirm("¿Desea eliminar al Owner?")){
      this.servicioOwner.eliminaOwner(ide).subscribe(datos =>{
        this.owners = datos;
      });
    }
  }

  irAmod(ide:any){

    this.ruta.navigate(['/form-owner/'+ide]);
  }

}
