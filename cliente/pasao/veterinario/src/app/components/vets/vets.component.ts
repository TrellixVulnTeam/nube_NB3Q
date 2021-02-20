import { Component, OnInit } from '@angular/core';
import { VetService } from "../../servicios/vet.service";
import { Vet } from "../../models/vet";
import { ActivatedRoute, Router } from '@angular/router';

@Component({
  selector: 'app-vets',
  templateUrl: './vets.component.html',
  styleUrls: ['./vets.component.css']
})
export class VetsComponent implements OnInit {

  public vets: Array<Vet>;

  constructor(private servicioVet: VetService,private ruta: Router, private route: ActivatedRoute) { }

  ngOnInit() {
    this.servicioVet.getVets().subscribe(
      datos => {
        console.log(datos);
        this.vets = datos;
      }
    );
  }

  borraVet(ident: any) {
    console.log("Estamos en borraVet: ",ident);
    if (confirm("¿Desea borrar el veterinario?")) {
      this.servicioVet.delVet(ident).subscribe(datos => {
        this.servicioVet.getVets().subscribe(
          datos => {
            this.vets = datos;
          }
        );
      });
    }
  }

  iraModVet(ide:any){
    this.ruta.navigate(['/form-vet-mod/'+ide]);
  }

}
