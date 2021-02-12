import { Owner } from '../../models/owner';
import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { OwnersService } from './../../servicios/owners.service';

@Component({
  selector: 'app-form-owners',
  templateUrl: './form-owners.component.html',
  styleUrls: ['./form-owners.component.css']
})
export class FormOwnersComponent implements OnInit {

  public owner : Owner;

  constructor(private servicioOwner: OwnersService, private ruta: Router, private route:ActivatedRoute) {
    this.owner = <Owner>{};
  }

  ngOnInit(): void {

  }

  enviar(owner: Owner){
    console.log(owner);
    this.servicioOwner.setOwner(owner).subscribe(
      datos=>{
        console.log(datos);
        this.ruta.navigate(['/owners']);
      },
      error => console.log("error", error));


  }


}
