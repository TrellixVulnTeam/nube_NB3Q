import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PetsAndVisitComponent } from './pets-and-visit.component';

describe('PetsAndVisitComponent', () => {
  let component: PetsAndVisitComponent;
  let fixture: ComponentFixture<PetsAndVisitComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PetsAndVisitComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PetsAndVisitComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
