import { Component, OnInit } from '@angular/core';
import { VetService } from "../../servicios/vet.service";
import { Vet } from "../../models/vet";

@Component({
  selector: 'app-vets',
  templateUrl: './vets.component.html',
  styleUrls: ['./vets.component.css']
})
export class VetsComponent implements OnInit {

  public vets:Array<Vet>;

  constructor(private servicioVet:VetService) { }

  ngOnInit() {
    this.servicioVet.getVets().subscribe(
      datos => {
        console.log(datos);
        this.vets = datos;
      }
    );
  }

}
