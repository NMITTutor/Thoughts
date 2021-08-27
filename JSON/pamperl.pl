#!/usr/bin/perl

  use Authen::PAM;
  use POSIX qw(ttyname);

  $service = "login";
  $username = $ARGV[0];
  $password = $ARGV[1];
  $tty_name = ttyname(fileno(STDIN));

  sub my_conv_func {
    my @res;
    while ( @_ ) {
        my $code = shift;
        my $msg = shift;
        my $ans = "";

        $ans = $username if ($code == PAM_PROMPT_ECHO_ON() );
        $ans = $password if ($code == PAM_PROMPT_ECHO_OFF() );

        push @res, (PAM_SUCCESS(),$ans);
    }
    push @res, PAM_SUCCESS();
    return @res;
  }

  ref($pamh = new Authen::PAM($service, $username, \&my_conv_func)) ||
         die "Error code $pamh during PAM init!";

  $res = $pamh->pam_set_item(PAM_TTY(), $tty_name);
  $res = $pamh->pam_authenticate;
  if($res != PAM_SUCCESS()){
     print "{\"JsnMsg\":\"AUTH_ERROR\",\"Msg\":\"",$pamh->pam_strerror($res),"\"}\n";
# unless $res == PAM_SUCCESS();
  }
  else {
        print "{\"JsnMsg\":\"AUTH_OK\",\"Msg\":\"OK\"}";
# unless $res != PAM_SUCCESS();
  }
