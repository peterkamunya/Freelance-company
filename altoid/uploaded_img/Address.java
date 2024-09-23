/**
 * @(#)Address.java
 *
 *
 * @author 
 * @version 1.00 2023/3/24
 */


public class Address {
	private String city;
	private String state;
	private String country;
	
    public Address() {
    	city="no name" ;
    	state="no name";
    	country="noname";	
    }
    public Address(String city,String s,String c)
    {
    	this.city=city;
    	state=s;
    	country=c;
    }
    public String getcity()
    {
    	return city;
    }
    public void setcity(String city)
    {
    	this.city=city;
    }
    public  String getstate()
    {
    	return state;
    }
    public void setstate(String state)
    {
    	this.state=state;
    }
    public  String getcountry()
    {
    	return country;
    }
    public void setcountry(String country)
    {
    	this.country=country;
    }
    public void display()
    {
    	System.out.println ("city");
    	System.out.println ("state");
    	System.out.println ("country");
    }
}
		


  