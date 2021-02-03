import { OwnersService } from './../../servicios/owners.service';
import { Component, OnInit } from '@angular/core';
import { Owner } from 'src/app/models/owner';

@Component({
  selector: 'app-owners',
  templateUrl: './owners.component.html',
  styleUrls: ['./owners.component.css']
})
export class OwnersComponent implements OnInit {

  public owners:Array<Owner>
  //public owners:Array<any>
  constructor(private servicioOwner: OwnersService) { }

  ngOnInit(): void {
    this.servicioOwner.getOwners().subscribe(
      datos => {
        console.log("Owners: ", datos);
        this.owners = datos;
      })
  }

}
