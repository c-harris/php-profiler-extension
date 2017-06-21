
PHP_ARG_ENABLE(algoweb, whether to enable Algoweb support,
[ --enable-algoweb      Enable Algoweb support])

AC_DEFUN([AC_ALGOWEB_CLOCK],
[
  have_clock_gettime=no

  AC_MSG_CHECKING([for clock_gettime])

  AC_TRY_LINK([ #include <time.h> ], [struct timespec ts; clock_gettime(CLOCK_MONOTONIC, &ts);], [
    have_clock_gettime=yes
    AC_MSG_RESULT([yes])
  ], [
    AC_MSG_RESULT([no])
  ])

  if test "$have_clock_gettime" = "no"; then
    AC_MSG_CHECKING([for clock_gettime in -lrt])

    SAVED_LIBS="$LIBS"
    LIBS="$LIBS -lrt"

    AC_TRY_LINK([ #include <time.h> ], [struct timespec ts; clock_gettime(CLOCK_MONOTONIC, &ts);], [
      have_clock_gettime=yes
      ALGOWEB_SHARED_LIBADD="$ALGOWEB_SHARED_LIBADD -lrt"
      AC_MSG_RESULT([yes])
    ], [
      LIBS="$SAVED_LIBS"
      AC_MSG_RESULT([no])
    ])
  fi

  if test "$have_clock_gettime" = "no"; then
    AC_MSG_CHECKING([for clock_get_time])

    AC_TRY_RUN([ #include <mach/mach.h>
      #include <mach/clock.h>
      #include <mach/mach_error.h>

      int main()
      {
        kern_return_t ret; clock_serv_t aClock; mach_timespec_t aTime;
        ret = host_get_clock_service(mach_host_self(), REALTIME_CLOCK, &aClock);

        if (ret != KERN_SUCCESS) {
          return 1;
        }

        ret = clock_get_time(aClock, &aTime);
        if (ret != KERN_SUCCESS) {
          return 2;
        }

        return 0;
      }
    ], [
      have_clock_gettime=yes
      AC_DEFINE([HAVE_CLOCK_GET_TIME], 1, [do we have clock_get_time?])
      AC_MSG_RESULT([yes])
    ], [
      AC_MSG_RESULT([no])
    ])
  fi

  if test "$have_clock_gettime" = "yes"; then
      AC_DEFINE([HAVE_CLOCK_GETTIME], 1, [do we have clock_gettime?])
  fi

  if test "$have_clock_gettime" = "no"; then
      AC_MSG_ERROR([clock_gettime is missing, but required])
  fi
])

if test "$PHP_ALGOWEB" != "no"; then
  AC_ALGOWEB_CLOCK

  AC_MSG_CHECKING(PHP version)
  export OLD_CPPFLAGS="$CPPFLAGS"
  export CPPFLAGS="$CPPFLAGS $INCLUDES"
  AC_TRY_COMPILE([#include <php_version.h>], [
#if PHP_MAJOR_VERSION > 5
#error  PHP > 5
#endif
  ], [
    subdir=php5
    AC_MSG_RESULT([PHP 5.x])
  ], [
    subdir=php7
    AC_MSG_RESULT([PHP 7.x])
  ])
  export CPPFLAGS="$OLD_CPPFLAGS"
  ALGOWEB_SOURCES="$subdir/spans.c"

  ALGOWEB_SOURCES="$ALGOWEB_SOURCES algoweb.c"

  PHP_SUBST([LIBS])
  PHP_SUBST([ALGOWEB_SHARED_LIBADD])
  PHP_NEW_EXTENSION(algoweb, $ALGOWEB_SOURCES, $ext_shared)
fi
