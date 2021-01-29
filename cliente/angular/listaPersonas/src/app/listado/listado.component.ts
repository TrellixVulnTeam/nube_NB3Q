import { Persona } from '../persona';
import { PAjaxService } from '../p-ajax.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-listado',
  templateUrl: './listado.component.html',
  styleUrls: ['./listado.component.css']
})
export class ListadoComponent implements OnInit {

  public lista:Persona[];

  constructor(private peti: PAjaxService) {
    this.peti.listar().subscribe(
      rs => {
        console.log("rs: ", rs);
        this.lista=rs;
      },
      error=>console.log("error: ", error))
    }

  ngOnInit(): void {
  }
  /*
   <td>{{p.ID}}</td>
            <td>{{p.DNI}}</td>
            <td>{{p.NOMBRE}}</td>
            <td>{{p.APELLIDOS}}</td>
            <td><button (click)="editar(p.id)" routerLink="persona-addmod/{{p.id}}">Edit</button></td>
  */

}
