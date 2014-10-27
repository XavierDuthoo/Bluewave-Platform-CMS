<div class="signinpanel">
        
        <div class="row">
            
            <div class="col-md-7">
                
                <div class="signin-info">
                    <div class="logopanel">
                        <a href="http://bluewavemarketing.be">Blue Wave Mobile Marketing</a>
                    </div><!-- logopanel -->
                
                    <div class="mb20"></div>
                
                    <h5><strong>Blue Wave Mobile Marketing beheer platform</strong></h5>
                    <ul>
                        <li><i class="fa fa-arrow-circle-o-right mr5"></i> Realtime statistieken</li>
                        <li><i class="fa fa-arrow-circle-o-right mr5"></i> Aanpassen inhoud</li>
                        <li><i class="fa fa-arrow-circle-o-right mr5"></i> Beheer gebruikers op het netwerk</li>
                        <li><i class="fa fa-arrow-circle-o-right mr5"></i> Coupon code management</li>
                        <li><i class="fa fa-arrow-circle-o-right mr5"></i> en nog veel meer...</li>
                    </ul>
                    <div class="mb20"></div>
                    <strong>Interesse? <a href="mailto:info@bluewavemarketing.be">Vraag een offerte aan</a></strong>
                </div><!-- signin0-info -->
            
            </div><!-- col-sm-7 -->
            
            <div class="col-md-5">
                
                <form method="post" action="index.php?page=users&amp;action=login">
                    <h4 class="nomargin">Aanmelden</h4>
                    <p class="mt5 mb20">Meld je aan om toegang te krijgen.</p>
                
                    <input type="text" class="form-control uname" name="email" value="{$smarty.post.email|@default:''}" placeholder="Email adres" />
                    <input type="password" class="form-control pword" name="password" placeholder="Wachtwoord" />
                    <a href="index.php?page=users&amp;action=request_link"><small>Wachtwoord vergeten?</small></a>
                    <button class="btn btn-success btn-block">Aanmelden</button>
                    
                </form>
            </div><!-- col-sm-5 -->
            
        </div><!-- row -->
        
        <div class="signup-footer">
            <div class="pull-left">
                &copy; 2014. Alle rechten voorbehouden. Blue Wave marketing
            </div>
        </div>
        
    </div><!-- signin -->