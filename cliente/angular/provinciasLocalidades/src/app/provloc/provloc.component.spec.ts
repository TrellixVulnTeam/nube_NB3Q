import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ProvlocComponent } from './provloc.component';

describe('ProvlocComponent', () => {
  let component: ProvlocComponent;
  let fixture: ComponentFixture<ProvlocComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ProvlocComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(ProvlocComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
