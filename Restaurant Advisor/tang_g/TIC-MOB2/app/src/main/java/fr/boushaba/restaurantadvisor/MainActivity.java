package fr.boushaba.restaurantadvisor;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.TextView;

public class MainActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        TextView me = (TextView) findViewById(R.id.menu);
        TextView aj = (TextView) findViewById(R.id.ajouter);
        TextView mo = (TextView) findViewById(R.id.modifier);
        TextView su = (TextView) findViewById(R.id.supprimer);
        TextView ch = (TextView) findViewById(R.id.chercher);
        TextView co = (TextView) findViewById(R.id.contact);


        me.setOnClickListener(new View.OnClickListener(){
            @Override
            public void onClick(View v) {

                startActivityMenu();
            }
        });
        co.setOnClickListener(new View.OnClickListener(){
            @Override
            public void onClick(View v) {

                startActivityContact();
            }
        });
        aj.setOnClickListener(new View.OnClickListener(){
            @Override
            public void onClick(View v) {

                startActivityAjouter();
            }
        });
        su.setOnClickListener(new View.OnClickListener(){
            @Override
            public void onClick(View v) {

                startActivitySupprimer();
            }
        });
        mo.setOnClickListener(new View.OnClickListener(){
            @Override
            public void onClick(View v) {

                startActivityModifier();
            }
        });
        ch.setOnClickListener(new View.OnClickListener(){
            @Override
            public void onClick(View v) {

                startActivityChercher();
            }
        });
    }
    private void startActivityMenu(){
        Intent intent = new Intent (this, Menu.class);
        startActivity(intent);
    }
    private void startActivityContact(){
        Intent intent = new Intent (this, Contact.class);
        startActivity(intent);
    }
    private void startActivityAjouter(){
        Intent intent = new Intent (this, Ajouter.class);
        startActivity(intent);
    }
    private void startActivitySupprimer(){
        Intent intent = new Intent (this, Supprimer.class);
        startActivity(intent);
    }
    private void startActivityModifier(){
        Intent intent = new Intent (this, Modifier.class);
        startActivity(intent);
    }
    private void startActivityChercher(){
        Intent intent = new Intent (this, Chercher.class);
        startActivity(intent);
    }
}
