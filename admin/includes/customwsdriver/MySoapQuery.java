package my.web.service;

import java.util.Map;

import java.io.*;

public class MySoapQuery {

 

      FileInputStream fis = null;

      

      public Object executeQuery( String queryText, Map parameterValues, Map queryProperties ){
         

            try{

                  fis = new FileInputStream( "c:/temp/Quote.xml");

                  

            }catch(Exception e){

                  //throwaway

                  e.printStackTrace();

            }

 

            return fis;

      }     

      

      

      public void close(){

            try{

                  fis.close();                  

            }catch(Exception e){

                  e.printStackTrace();

            }

            

      }

      

}
