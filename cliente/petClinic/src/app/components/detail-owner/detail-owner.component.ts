import { Owner } from '../../models/owner';
import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { OwnersService } from './../../servicios/owners.service';


@Component({
  selector: 'app-detail-owner',
  templateUrl: './detail-owner.component.html',
  styleUrls: ['./detail-owner.component.css']
})
export class DetailOwnerComponent implements OnInit {

  public owner : Owner;

  constructor(private servicioOwner: OwnersService, private ruta: Router, private route:ActivatedRoute) {
    this.owner = <Owner>{};
  }

  ngOnInit(): void {
    this.owner.id =this.route.snapshot.params["id"];
    console.log( this.owner.id );

    this.servicioOwner.getOwnerId(this.owner.id).subscribe(
      datos=>{
        console.log(datos);
        this.owner=datos;
      },
      error => console.log("error", error));
  }

}
