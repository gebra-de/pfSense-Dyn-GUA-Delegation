# Building DynGUA-PD

DynGUA-PD is a native FreeBSD/pfSense port with no compilation step. Build it
on an isolated amd64 FreeBSD or pfSense system that matches the target pfSense
release. Version 1.0.0 targets pfSense CE 2.9.0 and FreeBSD 16.

## Requirements

- A matching FreeBSD ports tree
- FreeBSD `make` and `pkg`
- PHP from the target pfSense environment
- `portlint`
- `socat`, also declared as a runtime dependency
- Optional `gettext-tools` for translation-catalog validation

Do not build release packages on a production firewall.

## Source Layout

Place the `rc-beta` branch at its matching port origin:

```sh
cd /usr/ports/net
git clone --branch rc-beta --single-branch \
    https://github.com/gebra-de/pfSense-Dyn-GUA-Delegation.git \
    pfSense-pkg-dgpd
cd pfSense-pkg-dgpd
```

## Validate and Build

```sh
portlint -AC
php -l files/conf/kea6-scripts/kea-pd-allocator.php
php -l files/usr/local/libexec/dgpd_worker.php
php -l files/usr/local/pkg/dgpd.inc
php -l files/usr/local/pkg/dgpd_kea.inc
msgfmt --check --check-format -o /dev/null translations/de_DE.po

make clean
make stage
make check-plist
make package
```

If the matching pfSense build system lacks FreeBSD source headers, pass its
running kernel version explicitly:

```sh
export OSVERSION="$(sysctl -n kern.osreldate)"
make clean stage check-plist package
```

This override does not make a mismatched ports tree compatible.

## Inspect and Test

```sh
pkg info -F work/pkg/pfSense-pkg-dgpd-1.0.0.pkg
tar -tf work/pkg/pfSense-pkg-dgpd-1.0.0.pkg
sha256 work/pkg/pfSense-pkg-dgpd-1.0.0.pkg
php tests/dgpd_test.php
```

Confirm the package name, version, origin, license, maintainer, packing list,
build ID, and source commit. Back up the pfSense configuration before testing
the package.

```sh
pkg add -f work/pkg/pfSense-pkg-dgpd-1.0.0.pkg
pkg check -s pfSense-pkg-dgpd
pkg info pfSense-pkg-dgpd
```

The binary package and its SHA256 checksum are GitHub release assets and are
not committed to the public source branch.
