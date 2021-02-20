import { Component, OnInit } from '@angular/core';
import { PAjaxService } from '../p-ajax.service';
import { THIS_EXPR } from '@angular/compiler/src/output/output_ast';
import { Router } from '@angular/router';

@Component({
  selector: 'app-listado',
  templateUrl: './listado.component.html',
  styleUrls: ['./listado.component.css']
})
export class ListadoComponent implements OnInit {

  private listaPer:any;
  

  constructor(private peticion: PAjaxService, private ruta: Router) { 
    this.peticion.petilistar().subscribe(res=>{
      console.log(res);
      this.listaPer = res;
    });
  }

  iraNuevaPersona(){
    this.ruta.navigate(['personas-add/-1']);
  }

  borraPersonas(iden:any){
    console.log(iden);
    this.peticion.petiBorrar(iden).subscribe(res=>{
      console.log(res);
      this.listaPer = res;
    });
  }

  modifica(iden:any){
    this.ruta.navigate(['personas-add/'+iden]);
  }

  ngOnInit() {
  }

}
