using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using System.Linq;

public class TestJsnDropNetworkService : MonoBehaviour
{
    public void jsnReceiverDel(JsnReceiver pReceived)
    {
        Debug.Log(pReceived.JsnMsg + " ..." + pReceived.Msg);
        // To do: parse and produce an appropriate response
    }

    public void jsnListReceiverDel(List<tblPerson> pReceivedList)
    {
        Debug.Log("Received items " + pReceivedList.Count());
        foreach (tblPerson lcReceived in pReceivedList)
        {
            Debug.Log("Received {" + lcReceived.PersonID + "," + lcReceived.Password + "," + lcReceived.HighScore.ToString()+"}");
        }// for

        // To do: produce an appropriate response
    }
    // Start is called before the first frame update
    void Start()
    {
        #region Test jsn drop
        JSONDropService jsDrop = new JSONDropService { Token = "PUT YOUR TOKEN HERE" };
        /*
        // Create table person
        jsDrop.Create<tblPerson, JsnReceiver>(new tblPerson
        {
            PersonID = "UUUUUUUUUUUUUUUUUUUUUUUUUUU",
            HighScore = 0,
            Password = "***************************"
        }, jsnReceiverDel);

        // Store people records
        jsDrop.Store<tblPerson,JsnReceiver> (new List<tblPerson>
        {
            new tblPerson{PersonID = "Jack",HighScore = 100,Password ="test"},
            new tblPerson{PersonID = "Jonny",HighScore = 200,Password ="test1"},
            new tblPerson{ PersonID = "Jill",HighScore = 300,Password ="test2"}
         }, jsnReceiverDel);
        
        // Retreive all people records
        jsDrop.All<tblPerson, JsnReceiver>(jsnListReceiverDel, jsnReceiverDel);
        
        jsDrop.Select<tblPerson,JsnReceiver>("HighScore > 200",jsnListReceiverDel, jsnReceiverDel);
        
        jsDrop.Delete<tblPerson, JsnReceiver>("PersonID = 'Jonny'", jsnReceiverDel);
        */
        jsDrop.Drop<tblPerson, JsnReceiver>(jsnReceiverDel);
        #endregion

    }

    // Update is called once per frame
    void Update()
    {
        
    }
}
